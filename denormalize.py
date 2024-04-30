import pandas as pd

def denormalize_sheet(sheet_name, df):
    # Rename columns to avoid conflicts when merging
    df.columns = [f"{sheet_name}_{col}" for col in df.columns]
    return df

def main():
    # Read Excel file
    excel_file = "Movies Data.xlsx"
    xls = pd.ExcelFile(excel_file)

    # Dictionary to store denormalized DataFrames
    denormalized_dfs = {}

    # Iterate over each sheet in the Excel file
    for sheet_name in xls.sheet_names:
        # Read data from each sheet
        df = pd.read_excel(xls, sheet_name)
        # Denormalize the data
        denormalized_df = denormalize_sheet(sheet_name, df)
        # Store denormalized DataFrame in the dictionary
        denormalized_dfs[sheet_name] = denormalized_df

    # Merge denormalized DataFrames into one
    merged_df = pd.concat(denormalized_dfs.values(), axis=1)

    # Write the merged DataFrame to a new Excel file
    merged_excel_file = "denormalized_data.xlsx"
    merged_df.to_excel(merged_excel_file, index=False)

    print("Denormalized data has been saved to", merged_excel_file)

if __name__ == "__main__":
    main()
