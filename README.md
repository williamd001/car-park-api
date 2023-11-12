# Car park API

## Overview

Welcome! 

This project contains a simple api for interacting with a car park booking system which allows users to manage
their bookings (create, update and delete).

They can also check which spaces are currently available (including the total cost of
parking there for a selected period of time).

<br>

## Endpoint documentation

For further information on each of the endpoints contained within the Car park API please consult the openapi.yml document

[Open Api Documentation](openapi.yml)

**Remember to include a Bearer token in the authorization header when interacting with any of the endpoint**

user_1 demo token: sanctum-token-user-1

user_2 demo token: sanctum-token-user-2

<br>

## Assumptions

- Price calculations are calculated using the `date_from` start of day till the end of day `date_to`. 

For example if a customer books a parking space from 2023-01-01 to 2023-01-05 that would be 5 days.


The customer in the above example would be entitled to park anytime from 2023-01-01 00:00:00 till 2023-01-05 23:59:59

<br>

## Future plans

- Switch to hourly based pricing system (currently calculated in days).
- Add seasonal pricing - something like a price_bands table could be used to achieve this
- Use custom user model instead of eloquent User model in the Sanctum auth implementation
