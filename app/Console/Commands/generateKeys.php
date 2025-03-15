<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class generateKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Public/Private key for enc/dec of AES key';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keyConfig = [
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];
        
        // Generate a new private key
        $res = openssl_pkey_new($keyConfig);
        openssl_pkey_export($res, $privateKey); // Export private key
        
        // Extract the public key from the private key
        $publicKey = openssl_pkey_get_details($res)["key"];
        
        // Define paths in the Laravel storage folder
        $privateKeyPath = storage_path('keys/private_key.pem');
        $publicKeyPath = storage_path('keys/public_key.pem');
        
        // Ensure the directory exists
        if (!file_exists(storage_path('keys'))) {
            mkdir(storage_path('keys'), 0755, true);
        }
        
        // Save the keys
        file_put_contents($privateKeyPath, $privateKey);
        file_put_contents($publicKeyPath, $publicKey);
        
        echo "Keys generated successfully:\n";
        echo "Private Key: $privateKeyPath\n";
        echo "Public Key: $publicKeyPath\n";
    }
}
