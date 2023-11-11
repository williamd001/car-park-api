
## Car park API


### Endpoint documentation

For further information on each of the endpoints contained within the Car park API please consult the openapi.yml document

[Open Api Documentation](openapi.yml)

## Assumptions

- Price calculations are calculated using the `date_from` start of day till the end of day `date_to`. 
For example if a customer books a parking space from 2023-01-01 till 2023-01-05 that would be 5 days.
- The customer is entitled to park anytime from 2023-01-01 00:00:00 till 2023-01-05 23:59:59

## Future plans

- Add authorisation logic to protect api endpoints
- Switch to hourly based pricing system (currently calculated in days).
