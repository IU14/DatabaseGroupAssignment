import pandas as pd

# Step 1: Read Excel Data
excel_file = "denormalized_data.xlsx"
df = pd.read_excel(excel_file)

# Step 2: Convert to JSON
json_data = df.to_json(orient="records")

# Step 3: Write to JSON File
json_file = "data.json"
with open(json_file, "w") as f:
    f.write(json_data)

print("Excel data has been converted to JSON and saved to", json_file)
