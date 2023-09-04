pipeline {
    agent any

    stages {
        stage('Hello') {
            steps {
                echo 'Hello World'
            }
        }

        stage('Build') {
            steps {
                sh 'composer install'
            }
        }

        stage('Deploy') {
            steps {
                echo 'Deploying'
            }
        }

        stage('Test') {
            steps {
               sh 'php vendor/bin/codecept run unit'
            }
        }

        stage('Release') {
            steps {
                 sh 'php artisan serve'
            }
        }
    }
}
