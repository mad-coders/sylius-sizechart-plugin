@madcoders_size_chart @managing_size_chart @editing_size_chart
Feature: Editing a size chart
    In order to ensure that customers have always up to date content
    As an Administrator
    I want to be able to edit size chart data

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui
    Scenario: Edit a size chart name
        Given there are size charts witch "code_360" and "Size Chart 360" in "English (United States)"
        And I am on size chart edit page for size chart code "code_360"
        When I change size chart name to "Madcoders Size Chart" in "English (United States)"
        And I click save changes button
        Then I should be notified that it has been successfully edited

    @ui
    Scenario: Change size chart file
        Given there are size charts witch "code_360" and "Size Chart 360" in "English (United States)"
        And  this size chart with code "code_360" has "madcoders.pdf" file in "English (United States)"
        And I am on size chart edit page for size chart code "code_360"
        When I replace size chart file with "sizechart-new.pdf" in "English (United States)"
        And I click save changes button
        Then I should be notified that it has been successfully edited
