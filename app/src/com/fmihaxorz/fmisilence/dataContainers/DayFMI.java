package com.fmihaxorz.fmisilence.dataContainers;

public class DayFMI 
{
	public int dayOfMonth;
	public int month;
	public DayFMI(int dayOfMonth, int month)
	{
		this.dayOfMonth = dayOfMonth;
		this.month = month;
	}
	public DayFMI()
	{
		this.dayOfMonth = 1;
		this.month = 1;
	}
}
