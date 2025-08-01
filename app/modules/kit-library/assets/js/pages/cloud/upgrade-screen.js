import { Heading, Text, Grid, Button } from '@elementor/app-ui';
import { useEffect } from 'react';
import PropTypes from 'prop-types';
import Content from '../../../../../../assets/js/layout/content';
import IndexHeader from '../index/index-header';
import IndexSidebar from '../index/index-sidebar';
import Layout from '../../components/layout';
import { AppsEventTracking } from 'elementor-app/event-track/apps-event-tracking';

export default function UpgradeScreen( {
	menuItems,
	forceRefetch,
	isFetching,
	cloudKitsData,
} ) {
	const hasSubscription = '' !== cloudKitsData?.subscription_id;
	const url = hasSubscription ? 'https://go.elementor.com/go-pro-cloud-website-templates-library-advanced/' : 'https://go.elementor.com/go-pro-cloud-website-templates-library/';

	useEffect( () => {
		AppsEventTracking.sendPageViewsWebsiteTemplates( elementorCommon.eventsManager.config.secondaryLocations.kitLibrary.cloudKitLibraryUpgrade );
	}, [] );

	return (
		<Layout
			sidebar={
				<IndexSidebar menuItems={ menuItems } />
			}
			header={
				<IndexHeader
					refetch={ () => {
						forceRefetch();
					} }
					isFetching={ isFetching }
				/>
			}
		>
			<div className="e-kit-library__index-layout-container">
				<Content className="e-kit-library__index-layout-main e-kit-library__connect-container">
					<Grid container alignItems="center" justify="center" direction="column" className="e-kit-library__error-screen">
						<i className="eicon-library-subscription-upgrade" aria-hidden="true"></i>
						<Heading
							tag="h3"
							variant="display-1"
							className="e-kit-library__error-screen-title"
						>
							{ __( 'It\'s time to level up', 'elementor' ) }
						</Heading>
						<Text variant="xl" className="e-kit-library__error-screen-description">
							{ __( 'Upgrade to Elementor Pro to import your own website template and save templates that you can reuse on any of your connected websites.', 'elementor' ) }
						</Text>
						<Button
							text={ __( 'Upgrade now', 'elementor' ) }
							url={ url }
							onClick={ () => {
								AppsEventTracking.sendKitsCloudUpgradeClicked( elementorCommon.eventsManager.config.secondaryLocations.kitLibrary.cloudKitLibrary );
							} }
							target="_blank"
							className="e-kit-library__upgrade-button"
						/>
					</Grid>
				</Content>
			</div>
		</Layout>
	);
}

UpgradeScreen.propTypes = {
	menuItems: PropTypes.array.isRequired,
	forceRefetch: PropTypes.func.isRequired,
	isFetching: PropTypes.bool.isRequired,
	cloudKitsData: PropTypes.object.isRequired,
};
