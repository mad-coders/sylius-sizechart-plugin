@madcoders_size_chart @managing_size_chart
Feature: Adding new size chart
    In order to enable customer to download size chart file related to the product
    As an Administrator
    I want to add new size chart with its display criteria

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a text product attribute "T-shirt brand" with code "t_shirt_brand"
        And the store has a product "MADCODERS T-shirt"
        And this product has a text attribute "T-shirt brand" with value "madcoders" in "English (United States)" locale
        And I am logged in as an administrator

    @ui
    Scenario: I can access size chart create page
        Given I am on size chart index page
        When I click create button
        Then I should be redirected to size chart create page

    @ui @javascript
    Scenario: Adding a new size chart
        Given I want to create a new size chart
        When I fill create form with following data:
            | field               | type              | value                               |
            | code                | field             | code-abc                            |
            | name                | translations      | Size Chart ABC                      |
        And I switch on enable toggle
        And I switch on "t_shirt_brand" attribute criteria option
        And I select "madcoders" for "t_shirt_brand" attribute criteria
        And I attach the "madcoders.pdf" file in "English (United States)"
        And I click submit button
        Then I should be notified that it has been successfully created
