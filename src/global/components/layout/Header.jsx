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
	if (!logoURL) {
		logoURL = 'https://tadamus.com';
	}

	return (
		<div className="tada-flex-row tada-admin-header">
			<div>
				<a href={logoURL} target="_blank" rel="noreferrer">
					<img src={logoLink} alt="logo" width="200px" />
				</a>
			</div>
			<div>
				<h1 className="tada-admin-page-heading">{pageTitle}</h1>
			</div>
		</div>
	);
};
