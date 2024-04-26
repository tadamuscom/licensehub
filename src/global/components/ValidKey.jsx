import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button } from '@global/Button';
import { Header } from '@settings/components/layout/Header';

export const ValidKey = ({ setHasValidKey }) => {
	const [error, setError] = useState('');
	const stopClick = async (e) => {
		e.preventDefault();

		const confirmation = confirm(
			__('Are you sure you want to stop the migration?', 'licensehub'),
		);

		if (!confirmation) return;

		const response = await apiFetch({
			path: '/tadamus/tadamm/v1/stop',
			method: 'POST',
			data: {
				nonce: tadamm_settings.nonce,
			},
		});

		if (!response.success) {
			setError(response.data.message);
			return;
		}

		setHasValidKey(false);
	};

	return (
		<div>
			<Header
				pageTitle="Migrate Monkey - Settings"
				logoLink={tadamm_settings.logo}
			/>
			<h2>{__('Great! You have a valid key!', 'licensehub')}</h2>
			<p>
				{__(
					'Your migration will begin shortly! You will receive an email when the migration begins.',
					'licensehub',
				)}
			</p>
			<p>
				{__(
					'Or you can check the status of your migration on your "',
					'licensehub',
				)}
				<a
					href="https://licensehub.com/migration/"
					target="_blank"
					rel="noreferrer">
					{__('migrations page', 'licensehub')}
				</a>
			</p>
			<p>
				<span
					style={{
						color: 'red',
					}}>
					{__('Danger!', 'licensehub')}
				</span>
				{__(
					'If you have to you can stop the process and erase all the progress by click on the following button.',
					'licensehub',
				)}
			</p>
			<Button onClick={stopClick}>{__('STOP MIGRATION', 'licensehub')}</Button>
			{error && (
				<p style={{ marginLeft: 0 }} id="tada-status">
					{error}
				</p>
			)}
		</div>
	);
};
