from sklearn.model_selection import train_test_split
from sklearn.svm import SVC
from sklearn.metrics import classification_report, confusion_matrix
import pandas as pd

# Load data
train_df = pd.read_csv("train.csv")

# Features and labels
X = train_df.drop(columns=["fake"])  # Features
y = train_df["fake"]  # Labels

# Split data into train and test sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train SVM model
svm_model = SVC(kernel='linear')
svm_model.fit(X_train, y_train)

# Predictions
y_pred = svm_model.predict(X_test)

# Convert predictions to boolean values
threshold = 0.5  # Adjust this threshold as needed
y_pred_bool = (y_pred > threshold)

# Evaluation
print("Confusion Matrix:")
print(confusion_matrix(y_test, y_pred_bool))
print("\nClassification Report:")
print(classification_report(y_test, y_pred_bool))
