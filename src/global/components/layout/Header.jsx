import { applyFilters } from '@wordpress/hooks';

/**
 * Add the main Header for the pages
 *
 * @since 1.0.2
 *
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Header = ({ logoURL, logoLink, pageTitle }) => {
	const logoSite = logoURL ? logoURL : 'https://tadamus.com';

	/**
	 * Filter before the header logo
	 *
	 * @action licensehub.header-before-logo
	 * @param {string} logoSite - The URL that should be applied to the logo.
	 * @param {string} logoLink - The URL of the logo image.
	 * @param {string} pageTitle - The title of the page.
	 */
	const headerBeforeLogo = applyFilters(
		'licensehub.header-before-logo',
		'',
		logoSite,
		logoLink,
		pageTitle,
	);

	/**
	 * Filter after the header logo
	 *
	 * @action licensehub.header-after-logo
	 * @param {string} logoSite - The URL that should be applied to the logo.
	 * @param {string} logoLink - The URL of the logo image.
	 * @param {string} pageTitle - The title of the page.
	 */
	const headerAfterLogo = applyFilters(
		'licensehub.header-after-logo',
		'',
		logoSite,
		logoLink,
		pageTitle,
	);

	return (
		<div className="flex flex-row items-center mt-2">
			{headerBeforeLogo}
			<div className="border-4 border-black border-solid border-t-0 border-b-0 border-l-0 pr-2">
				<a href={logoSite} target="_blank" rel="noreferrer">
					<img src={logoLink} alt="logo" width="200px" />
				</a>
			</div>
			{headerAfterLogo}
			<div className="pl-2">
				<h1 className="font-kanit font-bold font-xl uppercase">{pageTitle}</h1>
			</div>
		</div>
	);
};
