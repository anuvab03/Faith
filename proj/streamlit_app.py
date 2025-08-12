import streamlit as st
import joblib
from faith2 import apifyreq, preprocess_data, predict_fake_account  # Import from your Flask file

st.set_page_config(page_title="Instagram Fake Account Detector", page_icon="📷")

st.title("📷 Instagram Fake Account Detector")

# Input field
username = st.text_input("Enter Instagram Username:")

# Button to predict
if st.button("Check Account"):
    try:
        if not username.strip():
            st.warning("Please enter a username.")
        else:
            st.write(f"🔍 Checking username: **{username}**")
            
            # Fetch data
            dataset = apifyreq(username)
            if not dataset:
                st.error("❌ Username not found")
            else:
                # Preprocess
                processed_data = preprocess_data(dataset)
                if processed_data is None:
                    st.error("⚠️ Error in data preprocessing")
                else:
                    # Load model
                    svm_model = joblib.load('svm_model.pkl')
                    
                    # Predict
                    prediction = predict_fake_account(svm_model, processed_data)
                    if prediction is None:
                        st.error("⚠️ Error during prediction")
                    elif prediction:
                        st.error("🚨 This account is **FAKE**")
                    else:
                        st.success("✅ This account is **REAL**")
    except Exception as e:
        st.error(f"An error occurred: {e}")
