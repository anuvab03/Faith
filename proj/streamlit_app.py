import streamlit as st
import joblib
import pandas as pd
from apify_client import ApifyClient
import os
from dotenv import load_dotenv



# Configure page
st.set_page_config(page_title="Instagram Fake Account Detector", page_icon="📷")

def apifyreq(username):
    """Fetch Instagram data using Apify"""
    try:
        # Initialize the ApifyClient with your API token
        # load .env locally (on your computer)
        load_dotenv()
        api_key = os.getenv("API_TOKEN")
        client = ApifyClient(api_key)

        # Prepare the Actor input
        run_input = {"usernames": [username]}

        # Run the Actor and wait for it to finish
        run = client.actor("dSCLg0C3YEZ83HzYX").call(run_input=run_input)

        # Fetch and return Actor results from the run's dataset
        dataset_id = run["defaultDatasetId"]
        dataset_items = client.dataset(dataset_id).list_items().items
        print(dataset_items)
        return dataset_items
    except Exception as e:
        print(f"Error in apifyreq: {e}")
        return None

def preprocess_data(dataset):
    """Preprocess the dataset for model prediction"""
    try:
        # Extract relevant information from the dataset
        if isinstance(dataset, list) and len(dataset) > 0:
            dataset = dataset[0]  # Take the first element
        else:
            return None

        data = {
            'ProfilePic': dataset.get('profile_pic', 0),
            'UsernameLength': sum(c.isdigit() for c in dataset['username']) / len(dataset['username']) if 'username' in dataset else 0,
            'FullnameWords': len(dataset['fullName'].split()) if 'fullName' in dataset else 0,
            'FullnameLength': sum(c.isdigit() for c in dataset['fullName']) / len(dataset['fullName']) if 'fullName' in dataset else 0,
            'name==username': dataset['fullName'] == dataset['username'] if 'fullName' in dataset and 'username' in dataset else 0,
            'DescriptionLength': len(dataset['biography']) if 'biography' in dataset else 0,
            'private': dataset['private'] if 'private' in dataset else 0,
            'posts': dataset['postsCount'] if 'postsCount' in dataset else 0,
        }
        df = pd.DataFrame([data])
        return df
    except Exception as e:
        print(f"Error in preprocess_data: {e}")
        return None

def predict_fake_account(model, processed_data):
    """Make prediction using the trained model"""
    try:
        predictions = model.predict(processed_data)
        return bool(predictions[0])
    except Exception as e:
        print(f"Error in predict_fake_account: {e}")
        return None

# Main Streamlit App
st.title("📷 Instagram Fake Account Detector")
st.markdown("---")

# Sidebar for information
st.sidebar.header("ℹ️ About")
st.sidebar.info(
    "This tool uses machine learning to detect fake Instagram accounts based on profile features like username patterns, bio length, follower ratios, and more."
)

st.sidebar.header("🔍 How it works")
st.sidebar.markdown("""
1. Enter an Instagram username
2. The system fetches profile data
3. Machine learning model analyzes the features
4. Get prediction: Real or Fake account
""")

# Main content
st.subheader("Enter Instagram Username")
username = st.text_input("Username (without @):", placeholder="e.g., john_doe")

# Add some spacing
st.markdown("<br>", unsafe_allow_html=True)

# Prediction button with custom styling
col1, col2, col3 = st.columns([1, 2, 1])
with col2:
    check_button = st.button("🔍 Check Account", use_container_width=True)

if check_button:
    if not username.strip():
        st.warning("⚠️ Please enter a username.")
    else:
        # Create progress bar and status updates
        progress_bar = st.progress(0)
        status_text = st.empty()
        
        try:
            # Step 1: Fetching data
            status_text.text("🔍 Fetching profile data...")
            progress_bar.progress(25)
            
            dataset = apifyreq(username)
            
            if not dataset:
                st.error("❌ Username not found or profile is private/inaccessible")
                progress_bar.empty()
                status_text.empty()
            else:
                # Step 2: Processing data
                status_text.text("⚙️ Processing data...")
                progress_bar.progress(50)
                
                processed_data = preprocess_data(dataset)
                
                if processed_data is None:
                    st.error("⚠️ Error in data preprocessing")
                    progress_bar.empty()
                    status_text.empty()
                else:
                    # Step 3: Loading model
                    status_text.text("🤖 Loading ML model...")
                    progress_bar.progress(75)
                    
                    try:
                        svm_model = joblib.load('svm_model.pkl')
                    except FileNotFoundError:
                        st.error("❌ Model file not found. Please ensure 'svm_model.pkl' is in the same directory.")
                        progress_bar.empty()
                        status_text.empty()
                        st.stop()
                    
                    # Step 4: Making prediction
                    status_text.text("🔮 Making prediction...")
                    progress_bar.progress(100)
                    
                    prediction = predict_fake_account(svm_model, processed_data)
                    
                    # Clear progress indicators
                    progress_bar.empty()
                    status_text.empty()
                    
                    # Display results
                    st.markdown("---")
                    st.subheader(f"Results for @{username}")
                    
                    if prediction is None:
                        st.error("⚠️ Error during prediction")
                    elif prediction:
                        st.error("🚨 **This account appears to be FAKE**")
                        st.markdown("""
                        <div style='background-color: #ffebee; padding: 15px; border-radius: 10px; border-left: 5px solid #f44336;'>
                        <strong>⚠️ Warning:</strong> This account shows characteristics commonly associated with fake profiles.
                        </div>
                        """, unsafe_allow_html=True)
                    else:
                        st.success("✅ **This account appears to be REAL**")
                        st.markdown("""
                        <div style='background-color: #e8f5e8; padding: 15px; border-radius: 10px; border-left: 5px solid #4caf50;'>
                        <strong>✅ Good news:</strong> This account shows characteristics of an authentic profile.
                        </div>
                        """, unsafe_allow_html=True)
                    
                    # Display some processed features (optional)
                    with st.expander("📊 View Processed Features"):
                        st.dataframe(processed_data)

        except Exception as e:
            progress_bar.empty()
            status_text.empty()
            st.error(f"❌ An error occurred: {str(e)}")

# Footer
st.markdown("---")
st.markdown(
    "<div style='text-align: center; color: #666;'>Built with ❤️ using Streamlit and Machine Learning</div>",
    unsafe_allow_html=True
)