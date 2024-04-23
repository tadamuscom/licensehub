/**
 * Add a helper text under inputs
 *
 * @since 1.0.2
 *
 * @param className
 * @param content
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const HelperText = ({ className, content, ...props }) => {
	const defaultClasses = "tada-helper-text";

	return (
		<p
			className={className ? defaultClasses + " " + className : defaultClasses}
			{...props}
		>
			{content}
		</p>
	);
};
