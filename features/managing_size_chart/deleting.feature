@madcoders_size_chart @managing_size_chart @deleting_size_chart
Feature: Deleting a size chart
    In order to remove a size chart that is not longer in use
    As an Administrator
    I want to delete this size chart

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui
    Scenario: Delete a size chart
        Given there are size charts witch "code_360" and "Size Chart 360" in "English (United States)"
        And I am on size chart index page
        When I delete the size chart with code "code_360"
        Then I should be notified that it has been successfully deleted
