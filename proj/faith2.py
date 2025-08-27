import joblib
import pandas as pd
from apify_client import ApifyClient
from flask import Flask, request, jsonify, render_template,redirect, session

app = Flask(__name__)

app.secret_key = 'Anuvab@2003'
def apifyreq(username):
    try:
        # Initialize the ApifyClient with your API token
        client = ApifyClient("apify_api_iGddTYYzSjCcTHFYv8m0LBcMYZAiLR0bpY1j")

        # Prepare the Actor input
        run_input = {"usernames": [username]}

        # Run the Actor and wait for it to finish
        run = client.actor("dSCLg0C3YEZ83HzYX").call(run_input=run_input)

        # Fetch and return Actor results from the run's dataset (if there are any)
        dataset_id = run["defaultDatasetId"]
        dataset_items = client.dataset(dataset_id).list_items().items
        print(dataset_items)
        return dataset_items
    except Exception as e:
        print(f"Error in apifyreq: {e}")
        return None

def preprocess_data(dataset):
    try:
        # Extract relevant information from the dataset
        if isinstance(dataset, list) and len(dataset) > 0:
            dataset = dataset[0]  # Assuming the dataset is a list of dictionaries, take the first element
        else:
            return None  # Return None if dataset is empty or not a list of dictionaries

        data = {

            'ProfilePic': dataset.get('profile_pic', 0),
            'UsernameLength': sum(c.isdigit() for c in dataset['username']) / len(dataset['username']) if 'username' in dataset else 0,
            'FullnameWords': len(dataset['fullName'].split()) if 'fullName' in dataset else 0,
            'FullnameLength': sum(c.isdigit() for c in dataset['fullName']) / len(dataset['fullName']) if 'fullName' in dataset else 0,
            'name==username': dataset['fullName'] == dataset['username'] if 'fullName' in dataset and 'username' in dataset else 0,
            'DescriptionLength': len(dataset['biography']) if 'biography' in dataset else 0,
            'private': dataset['private'] if 'private' in dataset else 0,
            'posts': dataset['postsCount'] if 'postsCount' in dataset else 0,
            #'followers': dataset['followersCount'] if 'followersCount' in dataset else 0,
            #'follows': dataset['followsCount'] if 'followsCount' in dataset else 0
        }
        df = pd.DataFrame([data])
        return df
    except Exception as e:
        print(f"Error in preprocess_data: {e}")
        return None

@app.route('/')
def index():
    return render_template('dashboard1.php')

def predict_fake_account(model, processed_data):
    try:
        predictions = model.predict(processed_data)
        return bool(predictions[0])
    except Exception as e:
        print(f"Error in predict_fake_account: {e}")
        return None

@app.route('/predict', methods=['POST','GET'])
def predict():
    try:
        if 'username' in request.args:
            username = request.args.get('username')
        else:
            username = request.form['username']

        print(f"Received username: {username}")
        
        dataset = apifyreq(username)
        
        if not dataset:
            return jsonify({"prediction": "Username not found"})
        
        # Preprocess the data
        processed_data = preprocess_data(dataset)
        
        if processed_data is None:
            return jsonify({"prediction": "Error in data preprocessing"})
        
        # Load the pre-trained SVM model
        svm_model = joblib.load('proj/svm_model.pkl')
        
        # Predict whether the Instagram account is fake or not
        prediction = predict_fake_account(svm_model, processed_data)
        
        if prediction is None:
            return jsonify({"prediction": "Error during prediction"})
        
        if prediction:
            return jsonify({"prediction": "This account is fake"})
        else:
            return jsonify({"prediction": "This account is real"})
    except Exception as e:
        print(f"Error in /predict endpoint: {e}")
        return jsonify({"prediction": "An error occurred during prediction"}), 500

@app.route('/logout')
def logout():
    # Clear the session to log out the user
    session.clear()
    # Redirect to the PHP login page on localhost
    return redirect('http://localhost/proj/login-signup.php')

if __name__ == '__main__':
    app.run(debug=True, host='127.0.0.1', port='5000')

