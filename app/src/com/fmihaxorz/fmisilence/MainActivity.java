package com.fmihaxorz.fmisilence;

import android.media.AudioManager;
import android.net.Uri;
import android.os.Bundle;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.view.Menu;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ToggleButton;

public class MainActivity extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		thisAppContext = getApplicationContext();
		
        
        Intent service = new Intent(thisAppContext, SoundServiceFMI.class);
        thisAppContext.startService(service);

        
        if(SoundServiceFMI.isInitd() == false)
        {
        	SoundServiceFMI.setAppContext(thisAppContext);
        	SoundServiceFMI.setAudioMan((AudioManager)thisAppContext.getSystemService(Context.AUDIO_SERVICE));
        	SoundServiceFMI.init();
        }
        
		// Load up the toggle buttons
		ToggleButton serviceTB = (ToggleButton)findViewById(R.id.toggle_serviceState);
        ToggleButton updateTB = (ToggleButton)findViewById(R.id.toggle_updating);
        serviceTB.setChecked(SoundServiceFMI.isActive());
        updateTB.setChecked(SoundServiceFMI.isAutoUpdating());
        
        // update the text under the update button with the last message
	    setStatusBoxMessage(SoundServiceFMI.getLastMessage());
	    
	    // Load up the saved username in the username field
	    String savedUsername = getServiceUsername();
	    EditText et = (EditText)findViewById(R.id.edit_username);
    	et.setText(savedUsername);
	    
    	
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}
	
	
	
	
	
	private void setStatusBoxMessage(String message)
	{
		// the text box under the button "Update"
	    TextView tv = (TextView)findViewById(R.id.text_lastUpdated);
	    tv.setText(message);
	}
	private void setServiceUsername(String username)
	{
		SoundServiceFMI.setUsername(username);
	}
	private String getServiceUsername()
	{
		return SoundServiceFMI.getUsername();
	}
	
	
	
	
	
	public void toggle_serviceStateHandler(View v)
	{
		ToggleButton tb = (ToggleButton)v;
		boolean isToggleOn = tb.isChecked();
		boolean isServiceActive = SoundServiceFMI.isActive();
		if(isToggleOn == isServiceActive)
			return;
		SoundServiceFMI.setActive(isToggleOn);
	}
	
	public void toggle_updatingStateHandler(View v)
	{
		ToggleButton tb = (ToggleButton)v;
		boolean isToggleOn = tb.isChecked();
		boolean isServiceAutoUpd = SoundServiceFMI.isAutoUpdating();
		if(isToggleOn == isServiceAutoUpd)
			return;
		SoundServiceFMI.setAutoUpdating(isToggleOn);
	}
	
	public void button_updateScheduleHandler(View v)
	{
		// Username field
		EditText et = (EditText)findViewById(R.id.edit_username);
    	String uname = et.getText().toString();
    	if(uname.length()==0)
    	{
    		toaster("Please fill in your username first.");
    		return;
    	}
    	
    	
    	setStatusBoxMessage("Renewing...");
	    toaster("Renewing schedule for user "+uname);
    	
	    
	    if(getServiceUsername().equals(uname) == false)
	    	setServiceUsername(uname);
	    
	    
	    SoundServiceFMI.forceScheduleRenew();
	    String response = SoundServiceFMI.getLastMessage();

	    
	    setStatusBoxMessage(response);
	    toaster(response);
	}
	
	
	public void imageButton_headerClicked(View v)
	{
		Intent browserIntent = 
                new Intent(Intent.ACTION_VIEW, Uri.parse("http://silentfmi.outernetnotes.com/"));
		startActivity(browserIntent);
	}
	
	
	private Context thisAppContext = null;
	private void toaster(String msg)
    {
		Toast.makeText(thisAppContext, msg, Toast.LENGTH_SHORT ).show();
    }
}
