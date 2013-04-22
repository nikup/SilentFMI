package com.fmihaxorz.fmisilence.dataContainers;

import java.util.ArrayList;
import java.util.Date;

public class DayDataNode 
{
	public ArrayList<DataNode> intervals = null;
	public DayFMI onDay = null;
	public DayDataNode(DayFMI onDay, ArrayList<DataNode> intervals)
	{
		this.intervals = intervals;
		this.onDay = onDay;
	}
}
