pipeline {
    agent any
    environment{
        scannerHome = tool "mysonar";
    }
    stages {
        stage('Code') {
            steps {
                git branch: 'main', credentialsId: 'github', url: 'https://github.com/manju9676/HopeGiversBloodBank.git'
            }
        }
        stage('CQA'){
            steps{
               withSonarQubeEnv('mysonar') {
                sh "${scannerHome}/bin/sonar-scanner -D sonar.projectKey=bloodbank"
                } 
            }
        
        }
        stage('SonarChecks'){
            steps{
                waitForQualityGate abortPipeline: false, credentialsId: 'sonar'
            }
         }
         stage('ImageBuild'){
             steps{
                 sh 'docker build -t frontend .'
                 sh 'docker build -t database ./database'
             }
         }
        //  stage(ImageScan){
        //      steps{
        //          sh 'trivy image --exit-code 1 --severity CRITICAL frontend'
        //          sh 'trivy image --exit-code 1 --severity CRITICAL database'
        //      }
        //  }
        stage(ImageScan){
            steps{
                sh 'trivy image frontend'
                sh 'trivy image database'
            }
        }
        stage(ImageTag){
            steps{
                sh 'docker tag frontend manju9676/hopegiversbloodbank-frontend:$BUILD_NUMBER'
                sh 'docker tag database manju9676/hopegiversbloodbank-database:$BUILD_NUMBER'
            }
        }
        stage(ImagePush){
            steps{
                script{
                    withDockerRegistry(credentialsId: 'docker-hub') {
                    sh 'docker push manju9676/hopegiversbloodbank-frontend:$BUILD_NUMBER'
                    sh 'docker push manju9676/hopegiversbloodbank-database:$BUILD_NUMBER'
                    }
                }
        }
    }
}
}
