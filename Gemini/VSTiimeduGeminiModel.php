<?php
/**
 * Class VSTiimeduGeminiModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduGeminiModel extends VSModelBackend
{
    private static $__instance = null;
    public $geminiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=';
    public $geminiKey = '';
    public static function getInstance()
    {
        if (null === self::$__instance) {
            self::$__instance = new self();
        }

        return self::$__instance;
    }
    public function __construct()
    {
        parent::__construct();
        $this->geminiKey = VSSetting::s('tiimedi_gemini_key','AIzaSyC82DhbrlEUpJQhaGsZaC8tpv29pVzNy70');
        $this->geminiUrl .= $this->geminiKey;
    }

    public function ask($question = "")
    {
        if(empty($question)) return null;
        $data = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $question]
                    ]
                ]
            ]
        ];

        $data_string = json_encode($data);
        $ch = curl_init($this->geminiUrl);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            return null;
        }

        $data = json_decode($result, true);
        curl_close($ch);

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }
}