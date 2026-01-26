<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMService
{
    protected $projectId;
    
    public function __construct()
    {
        // Project ID from serviceAccount.json
        $this->projectId = 'quiz-earning-app-11fec'; 
    }

    public function getAccessToken()
    {
        $credentialsPath = storage_path('app/serviceAccount.json');
        
        if (!file_exists($credentialsPath)) {
            Log::error('FCM Service Account JSON not found at ' . $credentialsPath);
            return null;
        }

        try {
            $credentials = new ServiceAccountCredentials(
                ['https://www.googleapis.com/auth/firebase.messaging'],
                $credentialsPath
            );
            
            $token = $credentials->fetchAuthToken();
            return $token['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to get FCM access token: ' . $e->getMessage());
            return null;
        }
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return false;
        }

        if (empty($token)) {
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ];

        // Ensure all data values are strings (FCM requirement)
        $stringData = [];
        foreach ($data as $key => $value) {
            $stringData[$key] = (string) $value;
        }

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $stringData,
            ],
        ];

        try {
            $response = Http::withHeaders($headers)->post($url, $payload);

            if ($response->successful()) {
                Log::info('FCM Notification sent successfully to ' . $token);
                return true;
            } else {
                Log::error('FCM Notification failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('FCM Notification exception: ' . $e->getMessage());
            return false;
        }
    }
}
