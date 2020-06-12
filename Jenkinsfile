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

        stage('github_status') {
          steps {
            sh 'ls -al'
          }
        }

        stage('build_spring') {
          steps {
            sh 'cd springboot-jenkins-docker-slack && ls -al && chmod 755 gradlew && ls -al && ./gradlew clean test'
          }
        }

      }
    }

  }
  environment {
    stage = 'dev'
  }
}