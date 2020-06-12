pipeline {
  agent any
  stages {
    stage('test') {
      parallel {
        stage('test') {
          steps {
            sh 'echo "jenkins_blue_ocean test"'
          }
        }

        stage('test2') {
          steps {
            sh 'ls -al'
          }
        }

        stage('test3') {
          steps {
            sh 'cd springboot-jenkins-docker-slack && ls -al && chmod 755 gradlew && ls -al'
          }
        }

      }
    }

  }
  environment {
    stage = 'dev'
  }
}