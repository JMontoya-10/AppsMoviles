package com.example.noficacionesfirebase

import android.app.NotificationChannel
import android.app.NotificationManager
import android.content.Context
import android.os.Build
import android.util.Log
import androidx.core.app.NotificationCompat
import com.google.firebase.messaging.FirebaseMessagingService
import com.google.firebase.messaging.RemoteMessage

class FirebaseMessagingService : FirebaseMessagingService() {

    override fun onMessageReceived(remoteMessage: RemoteMessage) {
        super.onMessageReceived(remoteMessage)

        Log.d("FCM", "From: ${remoteMessage.from}")

        if (remoteMessage.data.isNotEmpty()) {
            Log.d("FCM", "Message data payload: ${remoteMessage.data}")

            val tipo = remoteMessage.data["tipo"]
            val mensaje = remoteMessage.data["mensaje"]

            val title = remoteMessage.data["title"] ?: tipo ?: "Nueva notificación"
            val body = remoteMessage.data["body"] ?: mensaje ?: "Mensaje recibido"

            showNotification(title, body)
        }

        remoteMessage.notification?.let { notification ->
            Log.d("FCM", "Message Notification Body: ${notification.body}")

            val title = notification.title ?: "Nueva notificación"
            val body = notification.body ?: "Mensaje recibido"

            showNotification(title, body)
        }
    }

    override fun onNewToken(token: String) {
        super.onNewToken(token)
        Log.d("FCM", "Refreshed token: $token")
    }

    private fun showNotification(title: String, body: String) {
        val channelId = "canal_predeterminado"
        val notificationId = System.currentTimeMillis().toInt()

        val notificationBuilder = NotificationCompat.Builder(this, channelId)
            .setSmallIcon(android.R.drawable.ic_dialog_info)
            .setContentTitle(title)
            .setContentText(body)
            .setPriority(NotificationCompat.PRIORITY_DEFAULT)
            .setAutoCancel(true)

        val notificationManager = getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val channelName = "Notificaciones generales"
            val channelDescription = "Canal para todas las notificaciones"
            val importance = NotificationManager.IMPORTANCE_DEFAULT

            val channel = NotificationChannel(channelId, channelName, importance).apply {
                description = channelDescription
            }

            notificationManager.createNotificationChannel(channel)
        }

        notificationManager.notify(notificationId, notificationBuilder.build())

        Log.d("FCM", "Notificación mostrada: $title - $body")
    }
}