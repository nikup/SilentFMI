package com.fmihaxorz.parsers;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.Vector;

import com.fmihaxorz.fmisilence.dataContainers.*;


public class DateIntervalParser 
{
	private String data;
	public DateIntervalParser(String data)
	{
		this.data = data;
	}
	
	/**
	 * @author georgi.gaydarov
	 * @return null on failure, vector with DayDataNodes on success
	 */
	public Vector<DayDataNode> parse()
	{
		Vector<DayDataNode> returnRes = new Vector();
		

		String[] dayDatas = this.data.split("\n"); // day1Data \n day2Data \n .....
		
		for(String day : dayDatas) // for each day, parse it
		{
			// create a new Day info node
			DayDataNode dayNode = new DayDataNode(new DayFMI(), new ArrayList());
			
			String[] dayModules = day.split("\\s+"); // dd.mm [space] hh.min|hh.min| ... 
			if(dayModules.length > 2)
				return null;
			
			String[] dayComponent = dayModules[0].split("\\."); // dd.mm
			if(dayComponent.length != 2)
				return null;
			
			dayNode.onDay.dayOfMonth = Integer.parseInt(dayComponent[0]); // day
			dayNode.onDay.month = Integer.parseInt(dayComponent[1]); // month
			
			if(dayModules.length > 1)
			{
			
				String[] intervals = dayModules[1].split("\\|"); // each interval in a string
				
				for(String interv : intervals)
				{
					String[] moments = interv.split("-"); // hh:mm-hh:mm
					if(moments.length != 2)
						return null;
					
					String[] momentTime1 = moments[0].split(":");
					if(momentTime1.length != 2)
						return null;
					int hour1 = Integer.parseInt(momentTime1[0]);
					int minute1 = Integer.parseInt(momentTime1[1]);
					
					String[] momentTime2 = moments[1].split(":");
					if(momentTime2.length != 2)
						return null;
					int hour2 = Integer.parseInt(momentTime2[0]);
					int minute2 = Integer.parseInt(momentTime2[1]);
					
					DataNode thisIntDN = new DataNode(new TimeOfDayFMI(hour1, minute1), new TimeOfDayFMI(hour2, minute2));
					dayNode.intervals.add(thisIntDN);
				}
			}
			
			returnRes.add(dayNode);
		}
		
		
		return returnRes;
	}
	
	/*
	public static void main(String[] args)
	{
		DateIntervalParser dip = new DateIntervalParser("14.4 7:00-8:10|14:20-15:10|23:00-24:10|\n15.4 7:00-12:10|14:20-15:10|23:00-24:10|\n");
		
		Vector<DayDataNode> v = dip.parse();
		int day = 15;
		int month = 4;
		
		for(DayDataNode ddn : v)
		{
			System.out.println(ddn.intervals.get(0).endTime.hour);
		}
	}*/
} 
