import type { LoginForm } from "../interfaces";
const BASE_URL = 'http://127.0.0.1:8000/api/login_check'

export async function login(loginForm : LoginForm){
    try {
        const response = await fetch(BASE_URL, {
            method: 'POST',
            body: JSON.stringify(loginForm),
            headers: {
                'Content-Type': 'application/json'
            }
        });
        if(response.ok){
            return response.json()
        }else {
            throw await response.json;
        }
    } catch (e) {
        throw e;
    }
}

