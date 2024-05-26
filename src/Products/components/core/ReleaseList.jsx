import apiFetch from '@wordpress/api-fetch';
import { Spinner } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export const ReleaseList = ({ productID }) => {
	const [loading, setLoading] = useState(true);
	const [releases, setReleases] = useState([]);

	useEffect(() => {
		(async () => {
			const result = await apiFetch({
				path: `/licensehub/v1/products/${productID}/get-releases`,
				method: 'GET',
			});

			if (!result.success) return console.error(result);

			if (result.data.length > 0) setReleases(result.data.releases);
			setLoading(false);
		})();
	}, [setLoading, setReleases, productID]);

	if (loading) return <Spinner className="ml-14 h-7 w-7 block" />;

	if (!loading && releases.length === 0)
		return <p>{__('No releases found.', 'licensehub')}</p>;

	return releases.map((release) => (
		<div key={release.id + release.version}>
			<p>{release.version}</p>
		</div>
	));
};
