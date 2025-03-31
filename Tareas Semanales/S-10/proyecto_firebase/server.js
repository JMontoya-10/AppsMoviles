const express = require('express');
const bodyParser = require('body-parser');
const admin = require('firebase-admin');
const path = require('path');

// Initialize the app
const app = express();
const port = process.env.PORT || 3000;

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

// Initialize Firebase Admin SDK
// Assuming the private key file is in the root directory
const serviceAccount = require('./serviceAccountKey.json');

admin.initializeApp({
  credential: admin.credential.cert(serviceAccount)
});

// Routes
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// API endpoint to send notification with token
app.post('/api/send-notification-token', async (req, res) => {
  try {
    const { subject, message, token } = req.body;
    
    if (!subject || !message || !token) {
      return res.status(400).json({ 
        success: false, 
        message: 'El asunto, mensaje y token son requeridos' 
      });
    }

    // Message structure for a single device
    const message_obj = {
      token: token,
      notification: {
        title: subject,
        body: message
      },
      data: {
        timestamp: new Date().toISOString(),
        messageId: Date.now().toString()
      }
    };

    // Send message to a specific device
    const response = await admin.messaging().send(message_obj);
    
    console.log('Notificación enviada:', response);
    
    res.status(200).json({
      success: true,
      message: 'Notificación enviada correctamente',
      response: response
    });
  } catch (error) {
    console.error('Error al enviar la notificación:', error);
    
    res.status(500).json({
      success: false,
      message: 'Error al enviar la notificación',
      error: error.message
    });
  }
});

// Start the server
app.listen(port, () => {
  console.log(`Servidor corriendo en http://localhost:${port}`);
});