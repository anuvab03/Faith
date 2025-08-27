import streamlit as st
import joblib
import pandas as pd
from apify_client import ApifyClient

# Cache the model loading - this runs only once!
@st.cache_resource
def load_model():
    """Load the pre-trained SVM model once and cache it"""
    return joblib.load('proj/svm_model.pkl')

# Cache the Apify client - avoid recreating it
@st.cache_resource
def get_apify_client():
    """Initialize Apify client once and cache it"""
    return ApifyClient("apify_api_iGddTYYzSjCcTHFYv8m0LBcMYZAiLR0bpY1j")

@st.cache_data(ttl=300)  # Cache for 5 minutes to avoid repeated API calls
def apifyreq(username):
    """Fetch Instagram data using Apify API"""
    try:
        client = get_apify_client()
        
        # Prepare the Actor input
        run_input = {"usernames": [username]}

        # Run the Actor and wait for it to finish
        run = client.actor("dSCLg0C3YEZ83HzYX").call(run_input=run_input)

        # Fetch and return Actor results from the run's dataset
        dataset_id = run["defaultDatasetId"]
        dataset_items = client.dataset(dataset_id).list_items().items
        return dataset_items
    except Exception as e:
        st.error(f"Error fetching data: {e}")
        return None

def preprocess_data(dataset):
    """Preprocess the Instagram data for model prediction"""
    try:
        if isinstance(dataset, list) and len(dataset) > 0:
            dataset = dataset[0]
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
        st.error(f"Error in data preprocessing: {e}")
        return None

def predict_fake_account(model, processed_data):
    """Predict if the account is fake using the trained model"""
    try:
        predictions = model.predict(processed_data)
        return bool(predictions[0])
    except Exception as e:
        st.error(f"Error during prediction: {e}")
        return None

# Streamlit App UI
def main():
    st.title("🔍 Instagram Fake Account Detector")
    st.markdown("Enter an Instagram username to check if the account is fake or real.")
    
    # Load the model once (cached)
    with st.spinner("Loading model..."):
        model = load_model()
    
    # User input
    username = st.text_input("Instagram Username:", placeholder="Enter username without @")
    
    if st.button("🔍 Analyze Account", type="primary"):
        if username:
            with st.spinner(f"Analyzing @{username}..."):
                # Fetch data from Apify
                dataset = apifyreq(username)
                
                if not dataset:
                    st.error("❌ Username not found or error fetching data")
                    return
                
                # Preprocess the data
                processed_data = preprocess_data(dataset)
                
                if processed_data is None:
                    st.error("❌ Error in data preprocessing")
                    return
                
                # Make prediction
                prediction = predict_fake_account(model, processed_data)
                
                if prediction is None:
                    st.error("❌ Error during prediction")
                    return
                
                # Display results
                st.markdown("---")
                if prediction:
                    st.error("🚨 **This account appears to be FAKE**")
                    st.markdown("⚠️ This account shows characteristics commonly associated with fake profiles.")
                else:
                    st.success("✅ **This account appears to be REAL**")
                    st.markdown("👍 This account shows characteristics of a legitimate profile.")
                
                # Show some account details if available
                if dataset and len(dataset) > 0:
                    account_data = dataset[0]
                    st.markdown("### Account Details:")
                    col1, col2, col3 = st.columns(3)
                    
                    with col1:
                        st.metric("Posts", account_data.get('postsCount', 'N/A'))
                    with col2:
                        st.metric("Followers", account_data.get('followersCount', 'N/A'))
                    with col3:
                        st.metric("Following", account_data.get('followsCount', 'N/A'))
                    
                    if account_data.get('fullName'):
                        st.write(f"**Full Name:** {account_data['fullName']}")
                    if account_data.get('biography'):
                        st.write(f"**Bio:** {account_data['biography'][:100]}...")
                    if account_data.get('private'):
                        st.write("🔒 **Private Account**")
        else:
            st.warning("⚠️ Please enter a username")
    
    # Add some information about the tool
    with st.expander("ℹ️ About This Tool"):
        st.markdown("""
        This tool uses machine learning to analyze Instagram accounts and predict whether they might be fake.
        
        **Features analyzed:**
        - Profile picture presence
        - Username characteristics
        - Full name patterns
        - Biography length
        - Account privacy settings
        - Post count
        
        **Note:** This is a prediction tool and may not be 100% accurate. Use it as a reference only.
        """)

if __name__ == "__main__":
    main()
