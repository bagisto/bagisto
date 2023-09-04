pipeline {
    agent any
    stages {
        stage('Checkout') {
            steps {
                // 检出代码，这一步可以根据需要配置SCM和仓库URL
                // 例如，可以使用Git SCM插件
                checkout([$class: 'GitSCM', branches: [[name: 'master']], 
                          userRemoteConfigs: [[url: 'https://github.com/Ryann727/bagisto.git']]])
            }
        }
        stage('Build') {
            steps {
                // 在这个阶段执行 composer install 命令
                sh 'composer install'
            }
        }
        stage('Release') {
            steps {
                // 在这个阶段执行 php artisan serve 命令（前台运行）
                sh 'php artisan serve'
            }
        }
        stage('Unit Test') {
            steps {
                // 在这个阶段执行单元测试
                sh 'php vendor/bin/codecept run unit'
            }
        }
        // 可以添加更多阶段，如部署等
    }
    post {
        success {
            // 构建成功时的操作
            // 在此处可以触发其他操作或通知
        }
        failure {
            // 构建失败时的操作
            // 在此处可以触发其他操作或通知
        }
    }
}
