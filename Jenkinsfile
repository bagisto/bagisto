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
                echo 'Building'
            }
        }

        stage('Deploy') {
            steps {
                echo 'Deploying'
            }
        }

        stage('Test') {
            steps {
                echo 'Testing'
                 bat 'php vendor/bin/codecept run unit'
            }
        }

        stage('Release') {
            steps {
                echo 'Releasing'
            }
        }
    }
}
