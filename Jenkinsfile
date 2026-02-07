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

pipeline {
    agent any
    parameters {
        string(name: 'DOCKER_REGISTRY', defaultValue: 'manju9676', description: 'Docker Hub Username')
        string(name: 'GIT_BRANCH', defaultValue: 'main', description: 'Git Branch')
    }
    environment {
        DOCKER_REGISTRY = "${params.DOCKER_REGISTRY}"
        GIT_BRANCH = "${params.GIT_BRANCH}"
        IMAGE_TAG = "${BUILD_NUMBER}"
        SONAR_SERVER = 'mysonar'
    }
    stages {
        stage('ImageTag') {
            steps {
                sh "docker tag frontend ${DOCKER_REGISTRY}/hopegiversbloodbank-frontend:${IMAGE_TAG}"
                sh "docker tag database ${DOCKER_REGISTRY}/hopegiversbloodbank-database:${IMAGE_TAG}"
            }
        }
    }
}