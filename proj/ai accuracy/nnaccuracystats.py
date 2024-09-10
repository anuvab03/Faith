import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.neural_network import MLPClassifier
from sklearn.metrics import classification_report, confusion_matrix

# Load data
train_df = pd.read_csv("train.csv")

# Features and labels
X = train_df.drop(columns=["fake"])  # Features
y = train_df["fake"]  # Labels

# Split data into train and test sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Scale features
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

# Train Neural Network model
nn_model = MLPClassifier(hidden_layer_sizes=(64, 64), activation='relu', max_iter=1000)
nn_model.fit(X_train_scaled, y_train)

# Predictions
y_pred = nn_model.predict(X_test_scaled)

# Evaluation
print("Confusion Matrix:")
print(confusion_matrix(y_test, y_pred))
print("\nClassification Report:")
print(classification_report(y_test, y_pred))
