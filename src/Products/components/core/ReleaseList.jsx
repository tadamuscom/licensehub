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

			if (result.data.length > 0) setReleases(result.data);
			setLoading(false);
		})();
	}, [setLoading, setReleases, productID]);

	if (loading) return <Spinner className="ml-14 h-7 w-7 block" />;

	if (!loading && releases.length === 0)
		return <p>{__('No releases found.', 'licensehub')}</p>;

	const trimSize = (str) => {
		const maxLength = 50;

		if (str.length > maxLength) return str.substring(0, maxLength) + '...';

		return str;
	};

	const generateURL = (id) => {
		const url = new URL(window.lchb_products.releases_url);
		url.searchParams.append('id', id);

		return url.toString();
	};

	return releases.map((release) => (
		<div key={release.id + release.version}>
			<a
				href={generateURL(release.id)}
				className="text-blue-500 no-underline hover:underline">
				<p className="text-blue-500">
					{release.version} - {trimSize(release.changelog)}
				</p>
			</a>
		</div>
	));
};
