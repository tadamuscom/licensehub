import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Custom hook to handle form submission
 *
 * @param defaultValues
 * @returns {{result: {field: string, type: string, message: string}, post: ((function(*, *): Promise<void>)|*), changeFormValue: changeFormValue, formData: unknown, loading: boolean}}
 */
export const useForms = (defaultValues) => {
	const [loading, setLoading] = useState(false);
	const [formData, setFormData] = useState(defaultValues);
	const [result, setResult] = useState({
		type: '',
		message: '',
		field: '',
	});

	/**
	 * Change the value of a form field
	 *
	 * @param key
	 * @param value
	 */
	const changeFormValue = (key, value) => {
		setFormData((prev) => ({
			...prev,
			[key]: value,
		}));
	};

	/**
	 * Set the result of the form submission to success
	 *
	 * @param message
	 */
	const setSuccess = (message) => {
		setResult(() => ({
			type: 'success',
			message,
			field: '',
		}));
	};

	/**
	 * Set the result of the form submission to error
	 *
	 * @param message
	 * @param field
	 */
	const setError = (message, field) => {
		setResult(() => ({
			type: 'error',
			message,
			field: field,
		}));
	};

	/**
	 * Make a post request
	 *
	 * @param endpoint
	 * @param nonce
	 * @returns {Promise<void>}
	 */
	const post = async (endpoint, nonce) => {
		setLoading(true);

		try {
			const response = await apiFetch({
				path: endpoint,
				method: 'POST',
				data: JSON.stringify({
					nonce: nonce,
					...formData,
				}),
			});

			response.success
				? setSuccess(response.data.message)
				: setError(response.data.message, response.data.field);

			return response;
		} catch (e) {
			setError(e.message, '');
		} finally {
			setLoading(false);
		}
	};

	const filePost = async (endpoint, nonce) => {
		setLoading(true);

		const data = new FormData();
		data.append('nonce', nonce);

		for (let [key, value] of Object.entries(formData)) {
			data.append(key, value);
		}

		try {
			const req = await fetch('http://skunkworks.test/wp-json' + endpoint, {
				method: 'POST',
				body: data,
			});

			if (!req.ok) {
				throw new Error(__('Server error!', 'licensehub'));
			}

			const response = await req.json();

			response.data.success
				? setSuccess(response.data.data.message)
				: setError(response.data.data.message, response.data.data.field);

			return response;
		} catch (e) {
			setError(e.message, '');
		} finally {
			setLoading(false);
		}
	};

	/**
	 *
	 * Make a put request
	 *
	 * @param endpoint
	 * @param id
	 * @param nonce
	 * @returns
	 */
	const put = async (endpoint, id, nonce) => {
		setLoading(true);

		try {
			const response = await apiFetch({
				path: endpoint,
				method: 'PUT',
				data: JSON.stringify({
					nonce: nonce,
					id,
					...formData,
				}),
			});

			response.success
				? setSuccess(response.data.message)
				: setError(response.data.message, response.data.field);

			return response;
		} catch (e) {
			setError(e.message, '');
		} finally {
			setLoading(false);
		}
	};

	return { loading, result, formData, changeFormValue, post, filePost, put };
};
