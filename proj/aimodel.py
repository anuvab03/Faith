import pandas as pd
from sklearn.svm import SVC
from sklearn.impute import SimpleImputer
import joblib
from sklearn.ensemble import RandomForestClassifier
import warnings
from sklearn.exceptions import DataConversionWarning
import re
def train_svm_model(train_df):
    # Ignore DataConversionWarning and UserWarning
    warnings.filterwarnings("ignore", category=DataConversionWarning)
    warnings.filterwarnings("ignore", category=UserWarning)

    # Handle missing values in the training data
    imputer = SimpleImputer(strategy='mean')
    X = imputer.fit_transform(train_df.drop(columns=["fake"]))
    y = train_df["fake"]
    
    # Train SVM model
    svm_model = SVC(kernel='linear')
    svm_model.fit(X, y)
    
    return svm_model



def train_random_forest_model(train_df):
    # Ignore specific UserWarnings about feature names
    warnings.filterwarnings("ignore", message=re.escape("X has feature names"), category=UserWarning)


    # Handle missing values in the training data
    imputer = SimpleImputer(strategy='mean')
    X = imputer.fit_transform(train_df.drop(columns=["fake"]))
    y = train_df["fake"]
    
    # Train Random Forest model
    rf_model = RandomForestClassifier(n_estimators=100, random_state=42)
    rf_model.fit(X, y)
    
    # Set feature names manually
    rf_model.feature_names = train_df.drop(columns=["fake"]).columns.tolist()
    
    return rf_model


if __name__ == "__main__":
    # Load training data
    train_df = pd.read_csv("train.csv")
    
    # Train RF model
    ai_model = train_random_forest_model(train_df)
    
    # Save the trained model to a file
    joblib.dump(ai_model, 'ai_model.pkl')
