pipeline {
    agent any
    
    environment {
        DEPLOY_SERVER = 'jd@212.85.25.94'
        DEPLOY_PATH   = '/var/www/html/sunset/gateway'
        DEPLOY_PATH_TEST = '/var/www/html/sunset/gateway-test'
        SSH_CRED      = '7f5db0fc-1f49-44d1-827b-9f8fbee846ea'
    }

    stages {
        stage('Deploy TEST Production') {
            steps {
                sshagent (credentials: [env.SSH_CRED]) {
                    withCredentials([
                        file(credentialsId: 'gateway_prod_test', variable: 'ENV_PROD_TEST'),
                        file(credentialsId: 'gateway_prod_testing', variable: 'ENV_PROD_TESTING')
                    ]) {
                        // Set permissions
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                sudo chown -R jd:www-data /var/www/html/sunset/gateway-test &&
                                sudo chmod -R 755 /var/www/html/sunset/gateway-test
                            '
                        """
                        
                        // Git pull with prune
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                cd /var/www/html/sunset/gateway-test &&
                                if [ ! -d ".git" ]; then
                                    git clone https://github.com/Jdv2022/project_1-gateway . 
                                else
                                    git fetch --prune
                                    git reset --hard origin/main
                                    git clean -fd
                                fi
                            '
                        """
                
                        // Upload .env files
                        sh """
                            scp -o StrictHostKeyChecking=no \$ENV_PROD_TEST jd@212.85.25.94:/var/www/html/sunset/gateway-test/.env
                            scp -o StrictHostKeyChecking=no \$ENV_PROD_TESTING jd@212.85.25.94:/var/www/html/sunset/gateway-test/.env.testing
                        
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                sudo chmod 644 /var/www/html/sunset/gateway-test/.env &&
                                sudo chmod 644 /var/www/html/sunset/gateway-test/.env.testing
                            '
                        """
                        
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                cd /var/www/html/sunset/gateway-test &&
                                composer install
                            '
                        """
                    
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                sudo chown -R jd:www-data /var/www/html/sunset/gateway-test &&
                                sudo chmod -R 755 /var/www/html/sunset/gateway-test
                            '
                        """
                        
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                cd /var/www/html/sunset/gateway-test &&
                                docker compose up -d
                            '
                        """
						sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                cd /var/www/html/sunset/gateway-test &&
                                docker compose exec app ./vendor/bin/phpunit
                            '
                        """
						sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                cd /var/www/html/sunset/gateway-test &&
                                docker compose down &&
        						docker network prune -f
                            '
                        """
                    }
                }
            }
        }
		stage('Deploy ACTUAL Production') {
            steps {
                sshagent (credentials: [env.SSH_CRED]) {
                    withCredentials([
                        file(credentialsId: 'gateway_prod_env', variable: 'ENV_FILE'),
                    ]) {
                        // Set permissions
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                sudo chown -R jd:www-data /var/www/html/sunset/gateway &&
                                sudo chmod -R 755 /var/www/html/sunset/gateway
                            '
                        """
                        
                        // Git pull with prune
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                cd /var/www/html/sunset/gateway &&
                                if [ ! -d ".git" ]; then
                                    git clone https://github.com/Jdv2022/project_1-gateway . 
                                else
                                    git fetch --prune
                                    git reset --hard origin/main
                                    git clean -fd
                                fi
                            '
                        """
                
                        // Upload .env files
                        sh """
                            scp -o StrictHostKeyChecking=no \$ENV_FILE jd@212.85.25.94:/var/www/html/sunset/gateway/.env
                        
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                sudo chmod 644 /var/www/html/sunset/gateway/.env
                            '
                        """
                        
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                cd /var/www/html/sunset/gateway &&
                                composer install
                            '
                        """
                    
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                sudo chown -R jd:www-data /var/www/html/sunset/gateway &&
                                sudo chmod -R 755 /var/www/html/sunset/gateway
                            '
                        """
                        
                        sh """
                            ssh -o StrictHostKeyChecking=no jd@212.85.25.94 '
                                cd /var/www/html/sunset/gateway &&
                                docker compose up -d
                            '
                        """
                    }
                }
            }
        }
    }
}