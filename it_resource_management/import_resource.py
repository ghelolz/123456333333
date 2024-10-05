import pandas as pd
import mysql.connector
from mysql.connector import Error

# Read CSV file using pandas
csv_file_path = 'resource.csv'
df = pd.read_csv(csv_file_path)

# Replace NaN values with empty strings
df = df.fillna('')

# Convert 'Date Allocated' to string to handle empty cells
df['Date Allocated'] = df['Date Allocated'].astype(str)

# Print the content of the CSV file to the command prompt
print("Content of the CSV file: ")
print(df)

try:
    # Connect to MariaDB
    connection = mysql.connector.connect(
        host='localhost',
        database='it_resource_management',
        user='root',
        password=' '
    )
    if connection.is_connected():
        cursor = connection.cursor()
        # Clear existing data in resources table
        cursor.execute("TRUNCATE TABLE resources")

        # Import data from CSV to MariaDB
        for _, row in df.iterrows():
            cursor.execute(
                "INSERT INTO resources (resource_name, type, status, allocated_to, date_allocated)"
                "VALUES (%s, %s, %s, %s, %s)",

                (row['Resource Name'], row['Type'],
                 row['Status'], row['Allocated To'], row['Date Allocated'] if
                 row['Date Allocated'] != '' else None)
            )
            connection.commit()
    print("Data imported successfully")

    # Fetch and display the content of the resources table

    cursor.execute("SELECT * FROM resources")
    rows = cursor.fetchall()
    print("\nCurrent contents of the 'resources' table:")
    print("ID     Resource Name     Type     Status     Allocated To     Date Allocated")
    print("="*95)
    for row in rows:
        # Format date_allocated for display
        date_allocated = row[5].strftime('%Y-%m-%d') if row[5]else ''

        print(f"{row[0]:<5}{row[1]:<20}{row[2]:<15}{row[3]:<15}{row[4]:<20}{date_allocated:<15}")

except Error as e:
    print(f"Error: {e}")

finally:
    if connection.is_connected():
        cursor.close()
        connection.close()