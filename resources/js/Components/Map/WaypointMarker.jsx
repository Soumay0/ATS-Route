import React from 'react';
import { Marker, Popup } from 'react-leaflet';
import L from 'leaflet';
import { renderToStaticMarkup } from 'react-dom/server';

export default function WaypointMarker({ waypoint }) {
    const createWaypointIcon = (type) => {
        const iconMarkup = renderToStaticMarkup(
            <div className="relative flex items-center justify-center">
                <div className="w-4 h-4 bg-accent-primary rounded-full animate-pulse border-2 border-white shadow-lg"></div>
                <div className="absolute inset-0 bg-accent-primary rounded-full animate-ping opacity-75"></div>
            </div>
        );
        return L.divIcon({ 
            html: iconMarkup, 
            className: '', 
            iconSize: [16, 16], 
            iconAnchor: [8, 8],
            popupAnchor: [0, -10]
        });
    };

    return (
        <Marker 
            position={[waypoint.lat, waypoint.lon]}
            icon={createWaypointIcon(waypoint.type)}
        >
            <Popup className="custom-popup">
                <div className="p-1 min-w-[150px]">
                    <h3 className="font-bold text-lg mb-1">{waypoint.name}</h3>
                    <div className="flex justify-between items-center text-sm border-b border-white/10 pb-1 mb-1">
                        <span className="text-text-secondary">Type</span>
                        <span className="bg-accent-primary/20 text-accent-primary px-2 rounded">{waypoint.type}</span>
                    </div>
                    <div className="flex justify-between items-center text-xs mt-2 text-text-secondary">
                        <span>Lat: {waypoint.lat.toFixed(4)}</span>
                        <span>Lon: {waypoint.lon.toFixed(4)}</span>
                    </div>
                </div>
            </Popup>
        </Marker>
    );
}
