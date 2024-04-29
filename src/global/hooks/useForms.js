import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';

export const useForms = (defaultValues) => {
	const [loading, setLoading] = useState(false);
	const [formData, setFormData] = useState(defaultValues);
	const [result, setResult] = useState({
		type: '',
		message: '',
		field: '',
	});

	const changeFormValue = (key, value) => {
		setFormData((prev) => ({
			...prev,
			[key]: value,
		}));
	};

	const setSuccess = (message) => {
		setResult(() => ({
			type: 'success',
			message,
			field: '',
		}));
	};

	const setError = (message, field) => {
		setResult(() => ({
			type: 'error',
			message,
			field: field,
		}));
	};

	const post = async (endpoint, nonce) => {
		setLoading(true);

		try {
			const res = await apiFetch({
				path: endpoint,
				method: 'POST',
				data: JSON.stringify({
					nonce: nonce,
					...formData,
				}),
			});

			res.success
				? setSuccess(res.data.message)
				: setError(res.data.message, res.data.field);
		} catch (e) {
			setError(e.message, '');
		} finally {
			setLoading(false);
		}
	};

	return { loading, result, formData, changeFormValue, post };
};
