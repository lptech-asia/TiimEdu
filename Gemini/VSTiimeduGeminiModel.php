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


    public function readIdentifyCard($filePath = null)
    {
        if(empty($filePath)) return [];
        $items = [];
        $data = array(
            'contents' => array(
                array(
                    'role' => 'user',
                    'parts' => array(
                        array('inline_data' => array('mime_type' => 'image/png', 'data' => base64_encode(file_get_contents($filePath)))),
                        array('text' => 'Extract Full name, Gender, DOB, contact address (Place of residence), ID number. Required to verify whether the uploaded image is an ID card or not. Validate input image is ID card and not empty ID number, if empty ID number return js with status false. result json format is status = true if validated or false, and data with all request output, if empty ID number data is empty array. Output as json text only, properties is full_name format')
                    )
                )
            )
        );
        
        $data_string = json_encode($data);
        $ch = curl_init($this->geminiUrl);
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $data = json_decode($result);
            if(!isset($data->candidates)) return $items;
            $data = $data->candidates[0]->content->parts[0]->text;
            $data = str_replace('```json', '', $data);
            $data = str_replace('```', '', $data);
            $items = json_decode($data);
        }
        
        curl_close($ch);
        return $items->data ?? [];
    }

    public function readPassport($filePath = null)
    {
        if(empty($filePath)) return [];
        $items = [];
        $data = array(
            'contents' => array(
                array(
                    'role' => 'user',
                    'parts' => array(
                        array('inline_data' => array('mime_type' => 'image/png', 'data' => base64_encode(file_get_contents($filePath)))),
                        array('text' => 'Extract data from this image as full_name, nantionality, id card number, date of issue, date of expiry, passport no . Output as json text only with properties full_name, passport_name, dob, passport_issue_at, passport_expired_at, passport_number if empty passport number return js with status false. result json format is status = true if validated or false, and data with all request output, if empty passport number data is empty array.')
                    )
                )
            )
        );
        
        $data_string = json_encode($data);
        $ch = curl_init($this->geminiUrl);
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $data = json_decode($result);
            if(!isset($data->candidates)) return $items;
            $data = $data->candidates[0]->content->parts[0]->text;
            $data = str_replace('```json', '', $data);
            $data = str_replace('```', '', $data);
            $items = json_decode($data);
        }
        
        curl_close($ch);
        return $items->data ?? [];
    }
}