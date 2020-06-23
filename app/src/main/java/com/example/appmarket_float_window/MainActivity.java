package com.example.appmarket_float_window;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.app.NotificationCompat;
import androidx.core.content.ContextCompat;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.provider.Settings;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Timer;
import java.util.TimerTask;

public class MainActivity extends AppCompatActivity {
    private Integer[] CHANNEL_ID = {0,1,2,3,4,5};
    private static final int CODE_PERMISSION = 2084;
    public final String EXTRA_URL = "com.example.appmarket_float_window";
    private Timer timer1;
    private String webtitles;
    public String message;
    private String url;
    public WebView webview;
    public Integer x=0;
    public int anti = 1;

    @Override
    protected void onResume() {
        toast_view("stop");
        for(int i=0;i==0;i++){
            int j = 0;
            j++;
        }
        super.onResume();
    }

    // 初始化
    @SuppressLint({"SetJavaScriptEnabled", "ObsoleteSdkInt"})
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Intent intent = getIntent();
        message = intent.getStringExtra(qrcode_scaner.EXTRA_MESSAGE);
        if(message != null){
            url = "https://appmarket.zeitfrei.xyz/token/" + message;
            webview = (WebView) findViewById(R.id.web);
            WebSettings webSettings = webview.getSettings();
            webSettings.setJavaScriptEnabled(true);
            webview.setWebViewClient(new WebViewClient());
            webview.loadUrl(url);
        }else{
            url = "https://appmarket.zeitfrei.xyz/token/null";
            webview = (WebView) findViewById(R.id.web);
            WebSettings webSettings = webview.getSettings();
            webSettings.setJavaScriptEnabled(true);
            webview.setWebViewClient(new WebViewClient());
            webview.loadUrl(url);
        }

        // 計時器
        // 時間單位：毫秒 ms      ||      1s = 1000ms
        //                                                       啟動時間          週期時間
        timer1 = new Timer();
        timer1.schedule(timerTask, 0, 500);

        // 權限
        int permissionCheck = ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA);
        if (permissionCheck != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(MainActivity.this,
                    new String[]{Manifest.permission.CAMERA},
                    1);
        }else{
            Toast.makeText(this, "已經拿到權限囉!", Toast.LENGTH_SHORT).show();
        }
    }

    //********************************************* 計時器區域 **************************************************//
    // 啟動 每 5 秒執行
    TimerTask timerTask = new TimerTask() {
        public void run() {
            Message message = new Message();
            message.what = 1;
            handler.sendMessage(message);
        }
    };

    @SuppressLint("HandlerLeak")
    final Handler handler = new Handler() {
        @SuppressLint("SetJavaScriptEnabled")
        @Override
        public void handleMessage(Message msg) {
            if (msg.what == 1) {
                // 執行事件
                int i = 0;i = i + 1;
                webtitles = webview.getTitle();
                TextView text = (TextView) findViewById(R.id.web_title);
                text.setText(webtitles);
                try {
                    JSONObject jsonObject = new JSONObject(webtitles);
                    String name = jsonObject.getString("status");
                    Button online = findViewById(R.id.online);      // 線上訂購
                    Button create = findViewById(R.id.add_order);  // 新增訂單
                    Button check  = findViewById(R.id.view_order); // 查看訂單
                    Button close  = findViewById(R.id.close);       // 關閉震動

                    if(name.equals("finish")){
                        // 完成訂單
                        if(anti == 1){
                            notify_system("完成訂單", "可以前往店家領取餐點", CHANNEL_ID[1]);
                        }

                        create.setVisibility(View.VISIBLE);
                        online.setVisibility(View.VISIBLE);
                        check.setVisibility(View.GONE);
                        close.setVisibility(View.VISIBLE);
                    }else if(name.equals("verify")){
                        // 接收訂單
                        create.setVisibility(View.VISIBLE);
                        online.setVisibility(View.GONE);
                        check.setVisibility(View.GONE);
                        close.setVisibility(View.GONE);
                    }else if(name.equals("wait")){
                        // 確認訂單(店家)
                        create.setVisibility(View.GONE);
                        online.setVisibility(View.GONE);
                        check.setVisibility(View.GONE);
                        close.setVisibility(View.GONE);
                    }else if(name.equals("wait_order")){
                        // 建立訂單
                        create.setVisibility(View.GONE);
                        online.setVisibility(View.GONE);
                        check.setVisibility(View.VISIBLE);
                        close.setVisibility(View.GONE);
                    }else if(name.equals("cancel")){
                        // 取消訂單
                        create.setVisibility(View.VISIBLE);
                        online.setVisibility(View.VISIBLE);
                        check.setVisibility(View.GONE);
                        close.setVisibility(View.GONE);
                    }else{
                        // 初始狀態
                        x=0;
                        create.setVisibility(View.VISIBLE);
                        online.setVisibility(View.VISIBLE);
                        check.setVisibility(View.GONE);
                        close.setVisibility(View.GONE);
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                TextView textr = findViewById(R.id.web_title);
                textr.setText(webtitles);
            }
        }
    };
    //*********************************************************************************************************//

    public void close_var(View view){
        if(anti == 1){
            anti = 0;
        }
    }
    // 建立通訊頻道
    private void createNotificationChannel() {
        // Create the NotificationChannel, but only on API 26+ because
        // the NotificationChannel class is new and not in the support library
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            CharSequence name = getString(R.string.channel_name);
            String description = getString(R.string.channel_description);
            int importance = NotificationManager.IMPORTANCE_DEFAULT;
            NotificationChannel channel = new NotificationChannel("Notification", name, importance);
            channel.setDescription(description);
            // Register the channel with the system; you can't change the importance
            // or other notification behaviors after this
            NotificationManager notificationManager = getSystemService(NotificationManager.class);
            notificationManager.createNotificationChannel(channel);
        }
    }

    // 吐司
    public void toast_view(String text){
        Toast.makeText(this, text, Toast.LENGTH_SHORT).show();
    }

    // 查看訂單
    @SuppressLint("SetJavaScriptEnabled")
    public void chceck_order (View view){
        WebView webview = (WebView) findViewById(R.id.web);
        WebSettings webSettings = webview.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webview.setWebViewClient(new WebViewClient());
        String mix = "https://appmarket.zeitfrei.xyz/wait_order";
        webview.loadUrl(mix);
    }

    // 查看訂單
    @SuppressLint("SetJavaScriptEnabled")
    public void online (View view){
        WebView webview = (WebView) findViewById(R.id.web);
        WebSettings webSettings = webview.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webview.setWebViewClient(new WebViewClient());
        String mix = "https://appmarket.zeitfrei.xyz/online";
        webview.loadUrl(mix);
    }

    // 建立訂單
    public void create_order(View view){
        Intent intent = new Intent(this, qrcode_scaner.class);
        startActivity(intent);
    }

    // 建立通訊原則
    public void notify_system(String title, String sub, Integer ID){
        createNotificationChannel();
        NotificationCompat.Builder builder = new NotificationCompat.Builder(this, "shop");

        // 設定通知系統之細項
        builder.setSmallIcon(R.drawable.ic_launcher_foreground)
                .setContentTitle(title)
                .setContentText(sub)
                .setStyle(new NotificationCompat.BigTextStyle()
                        .bigText(sub))
                .setPriority(NotificationCompat.PRIORITY_DEFAULT);

        // 設定震動效果
        builder.setVibrate(new long[] {0,1000,2000,1000});
        // 設定預設語音撥放
        Uri uri= RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        builder.setSound(uri);

        Notification notification = builder.build();
        NotificationManager manager = (NotificationManager)
                getSystemService(Context.NOTIFICATION_SERVICE);

        // 發布訊息
        manager.notify(ID, notification);
    }
}
