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

      }
    }

  }
  environment {
    stage = 'dev'
  }
}