# Sample Production Data Files

This directory contains sample CSV files for importing production data into the PT API APPS system.

## Available Sample Files

1. `sample_production_data_5.csv` - 5 sample entries for quick testing
2. `sample_production_data_10.csv` - 10 sample entries for medium testing
3. `sample_production_data_20.csv` - 20 sample entries for comprehensive testing

## File Format

All files follow the same CSV format with these columns:
- `transaction_number` - Unique transaction identifier (e.g., TRX001)
- `date` - Production date in MM/DD/YYYY format (e.g., 10/25/2025)
- `sp_number` - Surat Permintaan (SP) number (e.g., SP001)
- `vehicle_number` - Vehicle license plate number (e.g., B1234XYZ)
- `tbs_quantity` - Fresh Fruit Bunches quantity (e.g., 1000.5)
- `kg_quantity` - Kilogram quantity (e.g., 950.2)
- `division` - Division/Afdeling name (e.g., Afdeling A)
- `pks` - Palm Kernel Station (PKS) name (e.g., PKS 1)

## Date Format Note

The date format in these samples is MM/DD/YYYY (e.g., 10/25/2025). The import system has been updated to handle flexible date formats including:
- MM/DD/YYYY (US format)
- DD/MM/YYYY (European format)
- YYYY-MM-DD (ISO format)
- MM-DD-YYYY (Alternative format)

## Vehicle Numbers

Sample vehicle numbers used in these files:
- B1234XYZ
- B5678XYZ
- B9012XYZ

## Divisions

Sample divisions used in these files:
- Afdeling A
- Afdeling B
- Afdeling C

## PKS Stations

Sample PKS stations used in these files:
- PKS 1
- PKS 2
- PKS 3
- PKS 4
- PKS 5
- PKS 6
- PKS 7
- PKS 8
- PKS 9

## Usage Instructions

1. Navigate to "Data Produksi" in the application
2. Click the "Import" button
3. Select one of these sample files
4. The system will automatically create related records for vehicles, divisions, and PKS stations if they don't exist
5. View the imported data in the production table

## Notes

- All sample data is fictional and for testing purposes only
- The import system will automatically handle creation of related entities
- Duplicate transaction numbers will be rejected to maintain data integrity
- Quantities are in standard units (TBS in units, KG in kilograms)