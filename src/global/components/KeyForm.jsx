import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import classNames from 'classnames';
import { Button } from '@global/Button';
import { FormGroup } from '@global/FormGroup';
import { Input } from '@global/Input';
import { Label } from '@global/Label';

export const KeyForm = ({ setHasValidKey }) => {
	const [submitting, setSubmitting] = useState(false);
	const [key, setKey] = useState('');
	const [error, setError] = useState('');

	const handleSubmit = async (event) => {
		event.preventDefault();

		if (key.length < 1) {
			setError(__('Please enter a key', 'licensehub'));
			return;
		}

		setSubmitting(true);
		const response = await apiFetch({
			path: '/tadamus/tadamm/v1/settings',
			method: 'POST',
			data: {
				nonce: tadamm_settings.nonce,
				key: key,
			},
		});

		setSubmitting(false);
		if (!response.success) {
			setError(response.data.message);
			return;
		}

		setHasValidKey(true);
	};

	return (
		<div>
			<form onSubmit={handleSubmit} id="tada-settings-form">
				<FormGroup>
					<Label htmlFor="tadamm-migration-key">
						{__('Migration Key', 'licensehub')}
					</Label>
					<Input
						id="tadamm-migration-key"
						name="tadamm-migration-key"
						type="text"
						value={key}
						disabled={submitting}
						onChange={(e) => setKey(e.target.value)}
						className={classNames({
							'tada-error': error,
						})}
						error={error}
						helper={__(
							'The key that was emailed to you after the payment. You can also find it on the migration page.',
							'licensehub',
						)}
					/>
				</FormGroup>
				<FormGroup className="tada-form-submit">
					<Button type="submit" disabled={submitting}>
						{submitting
							? __('Validating...', 'licensehub')
							: __('Validate Key', 'licensehub')}
					</Button>
					{error && (
						<p id="tada-status">
							{__('Please fix the errors above ❌', 'licensehub')}
						</p>
					)}
				</FormGroup>
			</form>
		</div>
	);
};
