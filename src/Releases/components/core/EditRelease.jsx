import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import {
	Button,
	FormGroup,
	FormStatus,
	HeadingTwo,
	Input,
	Label,
	removeQueryParameter,
	Textarea,
	useForms,
} from '@global';

export const EditRelease = ({ releaseID }) => {
	const getReleaseByID = (id) =>
		window.lchb_releases.releases.find((release) => release.id === id);

	const { loading, result, formData, changeFormValue, put } = useForms({
		version: getReleaseByID(releaseID).version,
		changelog: getReleaseByID(releaseID).changelog,
	});

	const handleSubmit = async (e) => {
		e.preventDefault();

		const response = await put(
			'/licensehub/v1/releases/update-release',
			releaseID,
			window.lchb_releases.nonce,
		);
		removeQueryParameter('id');

		if (response.success) location.reload();
	};

	const handleDelete = async () => {
		const response = await apiFetch({
			path: '/licensehub/v1/releases/delete-release',
			method: 'DELETE',
			data: {
				nonce: window.lchb_releases.nonce,
				id: releaseID,
			},
		});

		if (!response.success) console.error(response);

		removeQueryParameter('id');
		location.reload();
	};

	return (
		<div>
			<HeadingTwo>{__('Edit Release', 'licensehub')}</HeadingTwo>
			<div>
				<form onSubmit={handleSubmit}>
					<FormGroup>
						<Label htmlFor="version">{__('Version', 'licensehub')}</Label>
						<Input
							type="text"
							id="version"
							name="version"
							value={formData.version}
							result={result}
							onChange={(e) => changeFormValue('version', e.target.value)}
							autoFocus={true}
						/>
					</FormGroup>
					<FormGroup>
						<Label htmlFor="changelog">{__('Changelog', 'licensehub')}</Label>
						<Textarea
							id="changelog"
							name="changelog"
							result={result}
							onChange={(e) => changeFormValue('changelog', e.target.value)}>
							{formData.changelog}
						</Textarea>
					</FormGroup>
					<FormGroup extraClass="tada-form-submit">
						<Button type="submit" loading={loading}>
							{loading
								? __('Loading...', 'licensehub')
								: __('Save Release', 'licensehub')}
						</Button>
						<FormStatus status={result} />
					</FormGroup>
				</form>
			</div>
			<div>
				<HeadingTwo>{__('Danger Zone', 'licensehub')}</HeadingTwo>
				<div className="flex flex-row gap-4 items-center">
					<div>
						<p className="font-bold my-0">
							{__('Delete this release', 'licensehub')}
						</p>
						<p className="my-2">
							{__(
								'Once you delete a release, there is no going back. Please be certain.',
								'licensehub',
							)}
						</p>
					</div>
					<Button variant="danger" onClick={handleDelete} className="mt-0">
						{__('Delete Release', 'licensehub')}
					</Button>
				</div>
			</div>
		</div>
	);
};
