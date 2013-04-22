package com.fmihaxorz.fmisilence.dataContainers;

public class TimeOfDayFMI 
{
	public int hour;
	public int minute;
	public TimeOfDayFMI(int hour, int minute)
	{
		this.hour = hour;
		this.minute = minute;
	}
	public TimeOfDayFMI()
	{
		this.hour = 0;
		this.minute = 0;
	}
}
