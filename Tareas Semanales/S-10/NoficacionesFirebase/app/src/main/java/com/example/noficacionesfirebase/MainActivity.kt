package com.example.noficacionesfirebase

import android.Manifest
import android.content.ClipboardManager
import android.content.ClipData
import android.content.Context
import android.content.pm.PackageManager
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import android.os.Build
import android.util.Log
import android.widget.Toast
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import com.google.firebase.messaging.FirebaseMessaging

class MainActivity : AppCompatActivity() {
    private lateinit var generatedTokenText: TextView
    private lateinit var getTokenButton: Button
    private lateinit var copyTokenButton: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_main)

        generatedTokenText = findViewById(R.id.generatedTokenText)
        getTokenButton = findViewById(R.id.getTokenButton)
        copyTokenButton = findViewById(R.id.copyTokenButton)

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            if(ContextCompat.checkSelfPermission(this, Manifest.permission.POST_NOTIFICATIONS) != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(this, arrayOf(Manifest.permission.POST_NOTIFICATIONS), 1)
            }
        }

        getFCMToken()

        getTokenButton.setOnClickListener {
            getFCMToken()
        }

        copyTokenButton.setOnClickListener {
            val token = generatedTokenText.text.toString()
            if (token.isNotEmpty() && token != "[TOKEN GENERADO]" && !token.startsWith("Error")) {
                val clipBoard = getSystemService(Context.CLIPBOARD_SERVICE) as ClipboardManager
                val clipData = ClipData.newPlainText("token", token)
                clipBoard.setPrimaryClip(clipData)
                Toast.makeText(this, "Token copiado al portapapeles", Toast.LENGTH_SHORT).show()
            } else {
                Toast.makeText(this, "No hay token para copiar", Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun getFCMToken() {
        try {
            FirebaseMessaging.getInstance().token.addOnCompleteListener { task ->
                if (task.isSuccessful) {
                    val token = task.result
                    generatedTokenText.text = token
                    Log.d("FCM", "Token: $token")

                    subscribeToTopics(token)
                } else {
                    generatedTokenText.text = "Error al obtener el token"
                    Log.e("FCM", "Error getting token", task.exception)
                }
            }
        } catch (e: Exception) {
            generatedTokenText.text = "Error: ${e.message}"
            Log.e("FCM", "Exception getting token", e)
        }
    }

    private fun subscribeToTopics(token: String) {
        FirebaseMessaging.getInstance().subscribeToTopic("all")
            .addOnCompleteListener { task ->
                val msg = if (task.isSuccessful) {
                    Log.d("FCM", "Subscribed to all topic")
                    "Suscrito a topic 'all'"
                } else {
                    Log.e("FCM", "Topic subscription failed", task.exception)
                    "Error suscribiendo a topic"
                }
                Toast.makeText(this, msg, Toast.LENGTH_SHORT).show()
            }
    }

    override fun onRequestPermissionsResult(requestCode: Int, permissions: Array<out String>, grantResults: IntArray) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults)
        if(requestCode == 1) {
            if(grantResults.isNotEmpty() && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                Log.d("MainActivity", "Permiso concedido")
                getFCMToken()
            } else {
                Log.d("MainActivity", "Permiso denegado")
                Toast.makeText(this, "Los permisos son necesarios para notificaciones", Toast.LENGTH_SHORT).show()
            }
        }
    }
}