# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
  In order to navigate on the application
  As a user
  I want to access on different pages

  Scenario: A user can access to home page
    When a user sends a request to "/"
    Then the status code should be "200"

  Scenario: A anonymous user cannot access to add car page
    When a user sends a request to "/car/add"
    Then the status code should be "302"
    Then i should be redirected to "/registration"

  Scenario: A anonymous user can see an announcement
    When a user sends a request to "/show/1"
    Then the status code should be "200"
    Then the page should contains "Votre voiture avec l'id : 1"
    Then the page should contains "Modele : C5"
    Then the page should contains "Prix : 4350"
