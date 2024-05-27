import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';

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

	/**
	 *
	 * Make a put request
	 *
	 * @param endpoint
	 * @param parameters
	 * @param nonce
	 * @returns
	 */
	const put = async (endpoint, parameters, nonce) => {
		setLoading(true);

		try {
			const response = await apiFetch({
				path: endpoint,
				method: 'PUT',
				data: JSON.stringify({
					nonce: nonce,
					...parameters,
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

	return { loading, result, formData, changeFormValue, post, put };
};
