package com.fmihaxorz.fmisilence;

import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.media.AudioManager;
import android.os.IBinder;
import android.widget.Toast;

public class SoundServiceFMI extends Service {

	private static boolean isActive = false;
	private static boolean isAutoUpdating = false;
	private static String username = "";
	private static String lastMessage = "No message!";
	private static boolean initd = false;
	private static AudioManager audioMan = null;
	private static Context appContext = null;
	
	public static Context getAppContext() {
		return appContext;
	}
	public static void setAppContext(Context appContext) {
		SoundServiceFMI.appContext = appContext;
	}
	public static void setAudioMan(AudioManager audioMan) {
		SoundServiceFMI.audioMan = audioMan;
	}
	public static boolean isInitd() {
		return initd;
	}
	public static String getLastMessage() {
		return lastMessage;
	}
	public static void setLastMessage(String lastMessage) {
		SoundServiceFMI.lastMessage = lastMessage;
	}
	public static String getUsername() {
		return username;
	}
	public static void setUsername(String username) {
		SoundServiceFMI.username = username;
		mdp.setUsername(username);
	}
	public static boolean isAutoUpdating() {
		return isAutoUpdating;
	}
	public static void setAutoUpdating(boolean isAutoUpdating) {
		SoundServiceFMI.isAutoUpdating = isAutoUpdating;
	}
	public static boolean isActive() {
		return isActive;
	}
	public static void setActive(boolean isActive) {
		SoundServiceFMI.isActive = isActive;
	}

	
	
	
	
	
	
	@Override
	public IBinder onBind(Intent intent) {
		// TODO Auto-generated method stub
		return null;
	}
	
	
	
	
	
	
	private static Thread runnerT = null;
	private static Thread updaterT = null;
	private static MadDataProvider mdp = null;
	@Override
	public int onStartCommand(Intent intent, int flags, int startId) {
		if(initd == false)
		{
			init();
		}
	    return Service.START_STICKY;
	}

	 
	
	public static void init() 
	{
		initd = true;
		mdp = new MadDataProvider(appContext);
		username = mdp.getUsername();
		
		// MAKE A THREAD!
		runnerT = new Thread(new Runnable() {
	        public void run() {
	        	while(audioMan == null) // wait for the audio manager to be set
	        	{
	        		try{ Thread.sleep(500);}
            		catch(Exception e){}
	        	}
	        	
	        	boolean haveISilenced = audioMan.getRingerMode() == AudioManager.RINGER_MODE_VIBRATE || audioMan.getRingerMode() == AudioManager.RINGER_MODE_SILENT;
	        	boolean haveIEnabled = !haveISilenced;
	            while(true)
	            {
	            	if(isActive)
	            	{
	            		boolean shouldBeSilent = mdp.shouldItBeSilent();
	            		
	            		if(shouldBeSilent)
	            		{
	            			if(haveIEnabled)
	            			{
	            				audioMan.setRingerMode(AudioManager.RINGER_MODE_VIBRATE);
	            				haveISilenced = true;
	            				haveIEnabled = false;
	            			}
	            		}
	            		else
	            		{
	            			if(haveISilenced)
	            			{
	            				audioMan.setRingerMode(AudioManager.RINGER_MODE_NORMAL);
	            				haveIEnabled = true;
	            				haveISilenced = false;
	            			}
	            		}
	            		
	            	}
	            	
	            	try
            		{
            		Thread.sleep(1000*30); // 30 seconds
            		}
            		catch(Exception e)
            		{
            			//
            		}
	            }
	        }
	    });
		runnerT.start();
		
		updaterT = new Thread(new Runnable() {
	        public void run() {
	            while(true)
	            {
	            	if(isAutoUpdating)
	            	{
	            		mdp.forceRenew();
	            	}
	            	
	            	try
            		{
            		Thread.sleep(1000*60*60*6); // 6 hours
            		}
            		catch(Exception e)
            		{
            			//
            		}
	            }
	        }
	    });
		updaterT.start();
		
		
	}
	
	
	
	static public void forceScheduleRenew()
	{
		 mdp.forceRenew();
	}
	
	
}
