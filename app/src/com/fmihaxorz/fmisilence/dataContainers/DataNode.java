package com.fmihaxorz.fmisilence.dataContainers;

import java.util.Date;

public class DataNode
{
	public TimeOfDayFMI startTime;
	public TimeOfDayFMI endTime;
	public DataNode(TimeOfDayFMI startTime, TimeOfDayFMI endTime)
	{
		this.startTime = startTime;
		this.endTime = endTime;
	}
}