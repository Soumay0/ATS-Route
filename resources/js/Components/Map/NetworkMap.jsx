import React from 'react';
import { MapContainer, TileLayer, LayersControl, FeatureGroup } from 'react-leaflet';
import MarkerClusterGroup from 'react-leaflet-cluster';
import { useTheme } from '../../Contexts/ThemeContext';
import WaypointMarker from './WaypointMarker';
import RouteLine from './RouteLine';
import LiveFlightOverlay from './LiveFlightOverlay';
import MapLegend from './MapLegend';

const { BaseLayer, Overlay } = LayersControl;

export default function NetworkMap({ waypoints, routes, liveFlights }) {
    const { theme } = useTheme();

    return (
        <div className="relative h-full w-full rounded-2xl overflow-hidden">
            <MapContainer 
                center={[30.0, 45.0]} 
                zoom={3} 
                style={{ height: '100%', width: '100%' }}
                zoomControl={true}
                scrollWheelZoom={true}
            >
                <LayersControl position="topright">
                    <BaseLayer checked={theme === 'dark'} name="Google Earth (Satellite)">
                        <TileLayer
                            url="http://mt0.google.com/vt/lyrs=y&x={x}&y={y}&z={z}"
                            attribution="&copy; Google Maps"
                        />
                    </BaseLayer>
                    <BaseLayer checked={theme === 'light'} name="Google Maps (Standard)">
                        <TileLayer
                            url="http://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}"
                            attribution="&copy; Google Maps"
                        />
                    </BaseLayer>
                    <BaseLayer checked={false} name="Dark Theme Map">
                        <TileLayer
                            url="https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png"
                            attribution='&copy; <a href="https://carto.com/">CartoDB</a>'
                        />
                    </BaseLayer>

                    <Overlay checked name="Waypoints">
                        <MarkerClusterGroup chunkedLoading maxClusterRadius={40}>
                            {waypoints.map((wp) => (
                                <WaypointMarker key={`wp-${wp.id}`} waypoint={wp} />
                            ))}
                        </MarkerClusterGroup>
                    </Overlay>

                    <Overlay checked name="ATS Routes">
                        <FeatureGroup>
                            {routes.map((route) => {
                                const fromWp = waypoints.find(w => w.id === route.from);
                                const toWp = waypoints.find(w => w.id === route.to);
                                if (!fromWp || !toWp) return null;
                                return (
                                    <RouteLine 
                                        key={`route-${route.id}`} 
                                        route={route} 
                                        fromWp={fromWp} 
                                        toWp={toWp} 
                                    />
                                );
                            })}
                        </FeatureGroup>
                    </Overlay>

                    <Overlay checked name="Live Flights">
                        <FeatureGroup>
                            {liveFlights.map((flight) => (
                                <LiveFlightOverlay key={`lf-${flight.icao24}`} flight={flight} />
                            ))}
                        </FeatureGroup>
                    </Overlay>
                </LayersControl>
            </MapContainer>
            
            <MapLegend />
        </div>
    );
}
