import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Custom hook to handle form submission
 *
 * @param defaultValues
 * @returns {{result: {field: string, type: string, message: string}, post: ((function(*, *): Promise<void>)|*), updateFormValue: updateFormValue, formData: unknown, loading: boolean}}
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
	const updateFormValue = (key, value) => {
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

	const filePost = async (endpoint, nonce, ajaxUrl) => {
		setLoading(true);

		let attachmentID = false;

		if (formData.fileUpload) {
			try {
				const formObj = new FormData();
				formObj.append('action', 'lchb_create_release');
				formObj.append('nonce', nonce);
				formObj.append('file', formData.fileUpload);

				const fileReq = await fetch(ajaxUrl, {
					method: 'POST',
					body: formObj,
				});

				if (!fileReq.ok) throw Error(__('Error uploading file', 'licensehub'));

				const fileRes = await fileReq.json();

				if (!fileRes.success) throw Error(fileRes.data.message);

				attachmentID = fileRes.data.attachment_id;
			} catch (e) {
				setError(e.message, 'file-upload');
				setLoading(false);

				return { success: false };
			}
		}

		try {
			const reqData = {
				nonce,
				...formData,
			};

			if (attachmentID) reqData.attachmentID = attachmentID;

			const response = await apiFetch({
				path: endpoint,
				method: 'POST',
				data: JSON.stringify(reqData),
			});

			response.success
				? setSuccess(response.data.message)
				: setError(response.data.message, response.data.field);

			return response;
		} catch (e) {
			setError(e.message, '');

			return { success: false };
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

	return { loading, result, formData, updateFormValue, post, filePost, put };
};
