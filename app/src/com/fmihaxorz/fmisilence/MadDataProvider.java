package com.fmihaxorz.fmisilence;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.List;
import java.util.Vector;

import org.apache.http.*;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import com.fmihaxorz.fmisilence.dataContainers.DataNode;
import com.fmihaxorz.fmisilence.dataContainers.DayDataNode;
import com.fmihaxorz.parsers.DateIntervalParser;

import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.preference.PreferenceManager;

public class MadDataProvider {
	private final String SERVER_URL = "http://silentfmi.outernetnotes.com/getschedule.php?user=";
	//private final String SERVER_URL = "http://silentfmi.outernetnotes.com/test.php?user=";
	
	private String username = "";
	private final String prefsFN = "fmiPrefs";
	private final String namePrefkey = "name";
	private final String schedulePrefkey = "schedule";
	private Context appContext = null;
	private SharedPreferences settings = null;
	private String scheduleStringUnparsed = "";
	
	public String getUsername() {
		return username;
	}
	public void setUsername(String username) {
		this.username = username;
		
		Editor e = this.settings.edit();
		e.putString(namePrefkey, username);
		e.commit();
	}

	
	public MadDataProvider(Context appContext)
	{
		this.appContext = appContext;
		
		this.settings = this.appContext.getSharedPreferences(prefsFN, 0); // private prefs
		
		this.username = settings.getString(namePrefkey, "");
		this.scheduleStringUnparsed = settings.getString(schedulePrefkey, "");
    	
		if(this.username.length() == 0)
		{
			SoundServiceFMI.setLastMessage("Username not set.");
			return;
		}
		
		if(this.scheduleStringUnparsed.length() == 0)
		{
			SoundServiceFMI.setLastMessage("No schedule available. Consider renewing.");
			return;
		}
		
		// else, everything loaded fine
		renewAlgo();
	}

	
	
	public void forceRenew()
	{
		if(this.username.length() == 0)
		{
			SoundServiceFMI.setLastMessage("Specify username and press Update."); // should not happen!
			return;
		}
		
		if(getDataFromUrl() == false)
		{
			return;
		}
		
		if(renewAlgo() == false)
		{
			return;
		}
		
		// Update the SharedPreferences entry only if the new schedule is OK
		Editor e = this.settings.edit();
		e.putString(this.schedulePrefkey, this.scheduleStringUnparsed);
		e.commit();
		
		
		Date nowDate = new Date();
		DateFormat dateFormat = new SimpleDateFormat("dd.MM.yyyy, HH:mm:ss.");
		SoundServiceFMI.setLastMessage("User "+username+"'s schedule renewed on "+dateFormat.format(nowDate));
	}
	
	
	
	
	
	
	private Vector<DayDataNode> dayDataList = new Vector<DayDataNode>();
	
	private boolean renewAlgo()
	{
		Vector<DayDataNode> vect = null;
		DateIntervalParser dip = new DateIntervalParser(this.scheduleStringUnparsed);
		
		try
		{
			vect = dip.parse();
			if(vect == null)
				throw new Exception("Bad schedule format!");
		}
		catch(Exception e)
		{
			SoundServiceFMI.setLastMessage("Schedule parsing failed - "+e.getMessage());
			return false;
		}
		
		dayDataList = vect;
		SoundServiceFMI.setLastMessage("Schedule loaded.");
		return true;
	}
	
	
	
	
	private boolean getDataFromUrl()
	{
		String tadaaaURL = this.SERVER_URL + this.username;
		
		try 
		{
			HttpClient httpclient = new DefaultHttpClient();
			HttpResponse response = httpclient.execute(new HttpGet(tadaaaURL));
			StatusLine statusLine = response.getStatusLine();
			if(statusLine.getStatusCode() == HttpStatus.SC_OK)
			{
				ByteArrayOutputStream out = new ByteArrayOutputStream();
				response.getEntity().writeTo(out);
				out.close();
				String responseString = out.toString();
	        
				if(responseString.compareTo("nouser") == 0 || responseString.compareTo("nouser\n") == 0)
				{
					SoundServiceFMI.setLastMessage("No such user on the website. Have you registered?");
					return false;
				}
				
				
				this.scheduleStringUnparsed = responseString;
				return true;
			} 
			else 
			{
				response.getEntity().getContent().close();
				SoundServiceFMI.setLastMessage("Connecting to the server failed. Dunno what happened. "+statusLine.getStatusCode());
				return false;
			}
		} 
		catch (Exception e) 
		{
			SoundServiceFMI.setLastMessage("Exception occured during schedule downloading : "+e.getMessage());
			return false;
		}
	}
	
	
	
	/*
	boolean letshavefun = false;
	public boolean shouldItBeSilent()
	{
		letshavefun = !letshavefun;
		return letshavefun;
	}*/
	
	
	public boolean shouldItBeSilent()
	{
		Calendar cal = new GregorianCalendar();
		int nowDay = cal.get(Calendar.DAY_OF_MONTH);
		int nowMonth = cal.get(Calendar.MONTH) + 1; // first month is 1
		int nowHour = cal.get(Calendar.HOUR_OF_DAY);
		int nowMinute = cal.get(Calendar.MINUTE);
		
		
		
		DayDataNode ddn = null;
		for(DayDataNode d : dayDataList)
		{
			if(d.onDay.dayOfMonth == nowDay && d.onDay.month == nowMonth)
			{
				ddn = d;
				break;
			}
		}
		
		if(ddn == null)
		{
			SoundServiceFMI.setLastMessage("No schedule found for today. Consider renewing.");
			return false; // dont be silent if we can not find the day.
		}
		
		for(DataNode interv : ddn.intervals)
		{
			if(interv.startTime.hour*60 + interv.startTime.minute <= nowHour*60 + nowMinute)
			{
				if(interv.endTime.hour*60 + interv.endTime.minute > nowHour*60 + nowMinute)
				{
					return true;
				}
			}
		}
		
		return false;
	}
}
